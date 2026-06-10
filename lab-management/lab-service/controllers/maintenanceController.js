const db = require('../config/db');

exports.index = (req, res) => {
    const query = `
        SELECT m.id,
               m.maintenance_date,
               m.condition_before,
               m.condition_after,
               m.notes,
               i.item_name AS inventory_item_name,
               i.inventory_code,
               u.name AS performed_by_name,
               GROUP_CONCAT(CONCAT(b.name, ' (', bu.quantity_used, ' ', b.unit, ')') SEPARATOR ', ') AS bhp_used_info
        FROM inventory_maintenances m
        JOIN inventories i ON i.id = m.inventory_id
        LEFT JOIN users u ON u.id = m.performed_by
        LEFT JOIN maintenance_bhp_usages bu ON bu.maintenance_id = m.id
        LEFT JOIN bhp_items b ON b.id = bu.bhp_item_id
        GROUP BY m.id
        ORDER BY m.maintenance_date DESC, m.id DESC
    `;

    db.query(query, (err, results) => {
        if (err) return res.status(500).json({ error: err.message });
        res.json(results);
    });
};

exports.store = (req, res) => {
    const {
        inventory_id,
        condition_before,
        condition_after,
        bhp_item_id,
        bhp_used = 0,
        maintenance_date,
        notes,
        performed_by
    } = req.body;

    if (!inventory_id || !condition_before || !condition_after || !maintenance_date) {
        return res.status(422).json({ error: 'Inventaris, kondisi sebelum/sesudah, dan tanggal diperlukan.' });
    }

    db.beginTransaction((err) => {
        if (err) return res.status(500).json({ error: err.message });

        // 1. Insert into inventory_maintenances
        db.query(
            'INSERT INTO inventory_maintenances (inventory_id, condition_before, condition_after, maintenance_date, notes, performed_by) VALUES (?, ?, ?, ?, ?, ?)',
            [inventory_id, condition_before, condition_after, maintenance_date, notes, performed_by || null],
            (err, result) => {
                if (err) return db.rollback(() => res.status(500).json({ error: err.message }));
                
                const maintenanceId = result.insertId;

                // 2. Update inventories condition
                db.query(
                    'UPDATE inventories SET condition_status = ? WHERE id = ?',
                    [condition_after, inventory_id],
                    (err) => {
                        if (err) return db.rollback(() => res.status(500).json({ error: err.message }));

                        // 3. Handle BHP usage if provided
                        if (bhp_item_id && bhp_used > 0) {
                            db.query('SELECT stock FROM bhp_items WHERE id = ? FOR UPDATE', [bhp_item_id], (err, rows) => {
                                if (err) return db.rollback(() => res.status(500).json({ error: err.message }));
                                if (!rows.length) {
                                    return db.rollback(() => res.status(404).json({ error: 'Item BHP tidak ditemukan.' }));
                                }

                                const currentStock = rows[0].stock;
                                if (currentStock < bhp_used) {
                                    return db.rollback(() => res.status(422).json({ error: 'Stok BHP tidak mencukupi untuk penggunaan ini.' }));
                                }

                                db.query(
                                    'UPDATE bhp_items SET stock = stock - ? WHERE id = ?',
                                    [bhp_used, bhp_item_id],
                                    (err) => {
                                        if (err) return db.rollback(() => res.status(500).json({ error: err.message }));

                                        db.query(
                                            'INSERT INTO maintenance_bhp_usages (maintenance_id, bhp_item_id, quantity_used) VALUES (?, ?, ?)',
                                            [maintenanceId, bhp_item_id, bhp_used],
                                            (err) => {
                                                if (err) return db.rollback(() => res.status(500).json({ error: err.message }));

                                                db.commit((err) => {
                                                    if (err) return db.rollback(() => res.status(500).json({ error: err.message }));
                                                    res.json({ message: 'Maintenance log created successfully with BHP usage' });
                                                });
                                            }
                                        );
                                    }
                                );
                            });
                        } else {
                            db.commit((err) => {
                                if (err) return db.rollback(() => res.status(500).json({ error: err.message }));
                                res.json({ message: 'Maintenance log created successfully' });
                            });
                        }
                    }
                );
            }
        );
    });
};
