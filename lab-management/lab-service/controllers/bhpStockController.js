const db = require('../config/db');

// GET /api/bhp-stock/pending
// BHP items from procurement that are approved + finalized but not yet received into bhp_items
exports.pendingBhpItems = (req, res) => {
    const query = `
        SELECT pi.*, pd.title AS draft_title, pd.year AS draft_year
        FROM procurement_items pi
        JOIN procurement_drafts pd ON pd.id = pi.draft_id
        WHERE pi.item_type = 'bhp'
          AND pi.approved_status = 'approved'
          AND pd.status = 'finalized'
          AND pi.id NOT IN (SELECT procurement_item_id FROM bhp_items WHERE procurement_item_id IS NOT NULL)
        ORDER BY pd.year DESC, pi.item_name ASC
    `;
    db.query(query, (err, results) => {
        if (err) return res.status(500).json({ error: err.message });
        res.json(results);
    });
};

// POST /api/bhp-stock/receive
// Move procurement item to bhp_items and create initial stock log
exports.receiveBhp = (req, res) => {
    const { procurement_item_id, item_name, unit, initial_stock, created_by } = req.body;

    if (!item_name || !unit || initial_stock === undefined || !created_by) {
        return res.status(422).json({ error: 'item_name, unit, initial_stock, dan created_by wajib diisi.' });
    }

    const receiveDate = new Date().toISOString().split('T')[0];

    db.query(
        `INSERT INTO bhp_items (procurement_item_id, item_name, stock, unit, receive_date)
         VALUES (?, ?, ?, ?, ?)`,
        [procurement_item_id || null, item_name, initial_stock, unit, receiveDate],
        (err, result) => {
            if (err) return res.status(500).json({ error: err.message });

            const bhpItemId = result.insertId;

            // Create incoming stock log
            db.query(
                `INSERT INTO bhp_stock_logs (bhp_item_id, type, quantity, description, created_by)
                 VALUES (?, 'incoming', ?, ?, ?)`,
                [bhpItemId, initial_stock, 'Penerimaan awal dari pengadaan', created_by],
                (err) => {
                    if (err) return res.status(500).json({ error: err.message });
                    res.json({ message: 'BHP berhasil diterima ke stok.', id: bhpItemId });
                }
            );
        }
    );
};

// POST /api/bhp-stock/usage
// Reduce stock, log outgoing/maintenance_usage, optionally create maintenance_bhp_usages record
exports.usageBhp = (req, res) => {
    const { bhp_item_id, quantity_used, type, description, created_by, maintenance_id } = req.body;

    if (!bhp_item_id || !quantity_used || !type || !created_by) {
        return res.status(422).json({ error: 'bhp_item_id, quantity_used, type, dan created_by wajib diisi.' });
    }

    if (!['outgoing', 'maintenance_usage'].includes(type)) {
        return res.status(422).json({ error: "type harus 'outgoing' atau 'maintenance_usage'." });
    }

    // Check current stock
    db.query('SELECT stock, item_name FROM bhp_items WHERE id = ?', [bhp_item_id], (err, rows) => {
        if (err) return res.status(500).json({ error: err.message });
        if (!rows.length) return res.status(404).json({ error: 'Item BHP tidak ditemukan.' });

        const currentStock = rows[0].stock;
        if (currentStock < quantity_used) {
            return res.status(422).json({
                error: `Stok tidak mencukupi. Stok saat ini: ${currentStock}, dibutuhkan: ${quantity_used}.`
            });
        }

        // Reduce stock
        db.query(
            'UPDATE bhp_items SET stock = stock - ? WHERE id = ?',
            [quantity_used, bhp_item_id],
            (err) => {
                if (err) return res.status(500).json({ error: err.message });

                // Create stock log
                db.query(
                    `INSERT INTO bhp_stock_logs (bhp_item_id, type, quantity, description, created_by)
                     VALUES (?, ?, ?, ?, ?)`,
                    [bhp_item_id, type, quantity_used, description || null, created_by],
                    (err) => {
                        if (err) return res.status(500).json({ error: err.message });

                        // If maintenance_usage and maintenance_id provided, create link
                        if (type === 'maintenance_usage' && maintenance_id) {
                            db.query(
                                `INSERT INTO maintenance_bhp_usages (maintenance_id, bhp_item_id, quantity_used)
                                 VALUES (?, ?, ?)`,
                                [maintenance_id, bhp_item_id, quantity_used],
                                (err) => {
                                    if (err) return res.status(500).json({ error: err.message });
                                    res.json({ message: 'Pemakaian BHP untuk maintenance berhasil dicatat.' });
                                }
                            );
                        } else {
                            res.json({ message: 'Pemakaian BHP berhasil dicatat.' });
                        }
                    }
                );
            }
        );
    });
};

// POST /api/bhp-stock/add
// Add incoming stock to existing BHP item
exports.addStock = (req, res) => {
    const { bhp_item_id, quantity, description, created_by } = req.body;

    if (!bhp_item_id || !quantity || !created_by) {
        return res.status(422).json({ error: 'bhp_item_id, quantity, dan created_by wajib diisi.' });
    }

    db.query('SELECT id FROM bhp_items WHERE id = ?', [bhp_item_id], (err, rows) => {
        if (err) return res.status(500).json({ error: err.message });
        if (!rows.length) return res.status(404).json({ error: 'Item BHP tidak ditemukan.' });

        db.query(
            'UPDATE bhp_items SET stock = stock + ? WHERE id = ?',
            [quantity, bhp_item_id],
            (err) => {
                if (err) return res.status(500).json({ error: err.message });

                db.query(
                    `INSERT INTO bhp_stock_logs (bhp_item_id, type, quantity, description, created_by)
                     VALUES (?, 'incoming', ?, ?, ?)`,
                    [bhp_item_id, quantity, description || 'Penambahan stok', created_by],
                    (err) => {
                        if (err) return res.status(500).json({ error: err.message });
                        res.json({ message: 'Stok berhasil ditambahkan.' });
                    }
                );
            }
        );
    });
};

// GET /api/bhp-stock/logs
exports.stockLogs = (req, res) => {
    const query = `
        SELECT l.*, b.item_name, b.unit, u.name AS created_by_name
        FROM bhp_stock_logs l
        JOIN bhp_items b ON b.id = l.bhp_item_id
        LEFT JOIN users u ON u.id = l.created_by
        ORDER BY l.created_at DESC
        LIMIT 200
    `;
    db.query(query, (err, results) => {
        if (err) return res.status(500).json({ error: err.message });
        res.json(results);
    });
};

// GET /api/bhp-stock/logs/:bhpItemId
exports.stockLogsByItem = (req, res) => {
    const { bhpItemId } = req.params;
    const query = `
        SELECT l.*, b.item_name, b.unit, u.name AS created_by_name
        FROM bhp_stock_logs l
        JOIN bhp_items b ON b.id = l.bhp_item_id
        LEFT JOIN users u ON u.id = l.created_by
        WHERE l.bhp_item_id = ?
        ORDER BY l.created_at DESC
    `;
    db.query(query, [bhpItemId], (err, results) => {
        if (err) return res.status(500).json({ error: err.message });
        res.json(results);
    });
};

// GET /api/bhp-stock/items
// List all BHP items with current stock
exports.allBhpItems = (req, res) => {
    const query = `
        SELECT bi.*
        FROM bhp_items bi
        ORDER BY bi.item_name ASC
    `;
    db.query(query, (err, results) => {
        if (err) return res.status(500).json({ error: err.message });
        res.json(results);
    });
};
