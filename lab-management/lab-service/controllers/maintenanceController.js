const db = require('../config/db');

exports.index = (req, res) => {
    const query = `
        SELECT m.id,
               m.inventory_item,
               m.condition,
               m.replacement_item,
               m.replaced_by,
               m.bhp_item_id,
               m.bhp_used,
               m.maintenance_date,
               m.notes,
               b.name AS bhp_name,
               b.unit AS bhp_unit
        FROM maintenance_logs m
        LEFT JOIN bhp_items b ON b.id = m.bhp_item_id
        ORDER BY m.maintenance_date DESC, m.id DESC
    `;

    db.query(query, (err, results) => {
        if (err) return res.status(500).json({ error: err.message });
        res.json(results);
    });
};

exports.store = (req, res) => {
    const {
        inventory_item,
        condition,
        replacement_item,
        replaced_by,
        bhp_item_id,
        bhp_used = 0,
        maintenance_date,
        notes,
    } = req.body;

    if (!inventory_item || !condition || !maintenance_date) {
        return res.status(422).json({ error: 'Inventaris, kondisi, dan tanggal diperlukan.' });
    }

    const insertLog = (callback) => {
        db.query(
            'INSERT INTO maintenance_logs (inventory_item, condition, replacement_item, replaced_by, bhp_item_id, bhp_used, maintenance_date, notes) VALUES (?, ?, ?, ?, ?, ?, ?, ?)',
            [inventory_item, condition, replacement_item, replaced_by, bhp_item_id, bhp_used, maintenance_date, notes],
            (err) => {
                if (err) return res.status(500).json({ error: err.message });
                callback();
            }
        );
    };

    if (bhp_item_id && bhp_used > 0) {
        db.query('SELECT stock FROM bhp_items WHERE id = ?', [bhp_item_id], (err, rows) => {
            if (err) return res.status(500).json({ error: err.message });
            if (!rows.length) {
                return res.status(404).json({ error: 'Item BHP tidak ditemukan.' });
            }

            const currentStock = rows[0].stock;
            if (currentStock < bhp_used) {
                return res.status(422).json({ error: 'Stok BHP tidak mencukupi untuk penggunaan ini.' });
            }

            insertLog(() => {
                db.query(
                    'UPDATE bhp_items SET stock = GREATEST(stock - ?, 0) WHERE id = ?',
                    [bhp_used, bhp_item_id],
                    (err) => {
                        if (err) return res.status(500).json({ error: err.message });
                        res.json({ message: 'Maintenance log created' });
                    }
                );
            });
        });
    } else {
        insertLog(() => {
            res.json({ message: 'Maintenance log created' });
        });
    }
};
