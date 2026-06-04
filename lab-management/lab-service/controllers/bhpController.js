const db = require('../config/db');

exports.index = (req, res) => {
    db.query('SELECT id, name, unit, stock, min_stock FROM bhp_items ORDER BY name', (err, results) => {
        if (err) return res.status(500).json({ error: err.message });
        res.json(results);
    });
};

exports.store = (req, res) => {
    const { name, unit, min_stock } = req.body;

    if (!name || !unit || min_stock === undefined) {
        return res.status(422).json({ error: 'Semua bidang BHP diperlukan.' });
    }

    db.query('SELECT id FROM bhp_items WHERE name = ?', [name], (err, rows) => {
        if (err) return res.status(500).json({ error: err.message });
        if (rows.length) {
            return res.status(409).json({ error: 'Nama BHP sudah ada. Gunakan nama lain.' });
        }

        db.query(
            'INSERT INTO bhp_items (name, unit, stock, min_stock) VALUES (?, ?, ?, ?)',
            [name, unit, 0, min_stock],
            (err, result) => {
                if (err) return res.status(500).json({ error: err.message });
                res.json({ message: 'BHP created', id: result.insertId });
            }
        );
    });
};

exports.update = (req, res) => {
    const { id } = req.params;
    const { name, unit, stock, min_stock } = req.body;

    if (!name || !unit || stock === undefined || min_stock === undefined) {
        return res.status(422).json({ error: 'Semua bidang BHP diperlukan.' });
    }

    db.query('SELECT id FROM bhp_items WHERE name = ? AND id != ?', [name, id], (err, rows) => {
        if (err) return res.status(500).json({ error: err.message });
        if (rows.length) {
            return res.status(409).json({ error: 'Nama BHP sudah ada. Gunakan nama lain.' });
        }

        db.query(
            'UPDATE bhp_items SET name = ?, unit = ?, stock = ?, min_stock = ? WHERE id = ?',
            [name, unit, stock, min_stock, id],
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
