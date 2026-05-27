const db = require('../config/db');

exports.index = (req, res) => {
    db.query('SELECT id, name, location FROM rooms', (err, results) => {
        if (err) return res.status(500).json({ error: err.message });
        res.json(results);
    });
};

exports.store = (req, res) => {
    const { name, location } = req.body;
    db.query('INSERT INTO rooms (name, location) VALUES (?, ?)', [name, location], (err, result) => {
        if (err) return res.status(500).json({ error: err.message });
        res.json({ message: 'Room created', id: result.insertId });
    });
};

exports.update = (req, res) => {
    const { id } = req.params;
    const { name, location } = req.body;
    db.query('UPDATE rooms SET name=?, location=? WHERE id=?', [name, location, id], (err) => {
        if (err) return res.status(500).json({ error: err.message });
        res.json({ message: 'Room updated' });
    });
};

exports.destroy = (req, res) => {
    const { id } = req.params;
    db.query('DELETE FROM rooms WHERE id=?', [id], (err) => {
        if (err) return res.status(500).json({ error: err.message });
        res.json({ message: 'Room deleted' });
    });
};
