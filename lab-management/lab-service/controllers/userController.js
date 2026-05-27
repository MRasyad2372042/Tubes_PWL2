const db = require('../config/db');

exports.index = (req, res) => {
    db.query('SELECT id, name, email, role FROM users', (err, results) => {
        if (err) return res.status(500).json({ error: err.message });
        res.json(results);
    });
};

exports.store = (req, res) => {
    const { name, email, password, role } = req.body;
    db.query('INSERT INTO users (name, email, password, role) VALUES (?, ?, ?, ?)',
        [name, email, password, role],
        (err, result) => {
            if (err) return res.status(500).json({ error: err.message });
            res.json({ message: 'User created', id: result.insertId });
        }
    );
};

exports.update = (req, res) => {
    const { id } = req.params;
    const { name, email, role } = req.body;
    db.query('UPDATE users SET name=?, email=?, role=? WHERE id=?',
        [name, email, role, id],
        (err) => {
            if (err) return res.status(500).json({ error: err.message });
            res.json({ message: 'User updated' });
        }
    );
};

exports.destroy = (req, res) => {
    const { id } = req.params;
    db.query('DELETE FROM users WHERE id=?', [id], (err) => {
        if (err) return res.status(500).json({ error: err.message });
        res.json({ message: 'User deleted' });
    });
};
