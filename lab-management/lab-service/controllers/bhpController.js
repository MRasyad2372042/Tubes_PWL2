const db = require('../config/db');

exports.index = (req, res) => {
    db.query('SELECT id, item_name AS name, unit, stock, 5 AS min_stock FROM bhp_items ORDER BY item_name', (err, results) => {
        if (err) return res.status(500).json({ error: err.message });
        res.json(results);
    });
};

exports.store = (req, res) => {
    const { name, unit } = req.body;

    if (!name || !unit) {
        return res.status(422).json({ error: 'Nama dan unit diperlukan.' });
    }

    db.query('SELECT id FROM bhp_items WHERE item_name = ?', [name], (err, rows) => {
        if (err) return res.status(500).json({ error: err.message });
        if (rows.length) {
            return res.status(409).json({ error: 'Nama BHP sudah ada. Gunakan nama lain.' });
        }

        db.query(
            'INSERT INTO bhp_items (item_name, unit, stock) VALUES (?, ?, ?)',
            [name, unit, 0],
            (err, result) => {
                if (err) return res.status(500).json({ error: err.message });
                res.json({ message: 'BHP created', id: result.insertId });
            }
        );
    });
};

exports.update = (req, res) => {
    const { id } = req.params;
    const { name, unit, stock } = req.body;

    if (!name || !unit || stock === undefined) {
        return res.status(422).json({ error: 'Semua bidang BHP diperlukan.' });
    }

    db.query('SELECT id FROM bhp_items WHERE item_name = ? AND id != ?', [name, id], (err, rows) => {
        if (err) return res.status(500).json({ error: err.message });
        if (rows.length) {
            return res.status(409).json({ error: 'Nama BHP sudah ada. Gunakan nama lain.' });
        }

        db.query(
            'UPDATE bhp_items SET item_name = ?, unit = ?, stock = ? WHERE id = ?',
            [name, unit, stock, id],
            (err) => {
                if (err) return res.status(500).json({ error: err.message });
                res.json({ message: 'BHP updated' });
            }
        );
    });
};

exports.destroy = (req, res) => {
    const { id } = req.params;
    db.query('DELETE FROM bhp_items WHERE id = ?', [id], (err) => {
        if (err) return res.status(500).json({ error: err.message });
        res.json({ message: 'BHP deleted' });
    });
};

