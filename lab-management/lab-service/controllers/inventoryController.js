const db = require('../config/db');
const path = require('path');
const fs = require('fs');

// Ensure uploads directory exists
const uploadDir = path.join(__dirname, '..', 'uploads');
if (!fs.existsSync(uploadDir)) {
    fs.mkdirSync(uploadDir, { recursive: true });
}

// GET /api/inventories/pending
// Items: type=inventory, approved_status=approved, from finalized drafts, NOT yet in inventories
exports.pendingItems = (req, res) => {
    const query = `
        SELECT pi.*, pd.title AS draft_title, pd.year AS draft_year
        FROM procurement_items pi
        JOIN procurement_drafts pd ON pd.id = pi.draft_id
        WHERE pi.item_type = 'inventory'
          AND pi.approved_status = 'approved'
          AND pd.status = 'finalized'
          AND pi.id NOT IN (SELECT procurement_item_id FROM inventories WHERE procurement_item_id IS NOT NULL)
        ORDER BY pd.year DESC, pi.item_name ASC
    `;
    db.query(query, (err, results) => {
        if (err) return res.status(500).json({ error: err.message });
        res.json(results);
    });
};

// GET /api/inventories
exports.index = (req, res) => {
    const query = `
        SELECT inv.*, r.name AS room_name, r.location AS room_location
        FROM inventories inv
        LEFT JOIN rooms r ON r.id = inv.room_id
        ORDER BY inv.created_at DESC
    `;
    db.query(query, (err, results) => {
        if (err) return res.status(500).json({ error: err.message });
        res.json(results);
    });
};

// POST /api/inventories/receive
// Expects multipart form data with fields + files (qr_code, photo)
exports.receiveItem = (req, res) => {
    const {
        procurement_item_id,
        item_name,
        inventory_code,
        receive_date,
        room_id,
    } = req.body;

    if (!item_name || !receive_date) {
        return res.status(422).json({ error: 'item_name dan receive_date wajib diisi.' });
    }

    const qr_code_path = req.files && req.files['qr_code'] ? '/uploads/' + req.files['qr_code'][0].filename : null;
    const barcode_path = req.files && req.files['barcode'] ? '/uploads/' + req.files['barcode'][0].filename : null;
    const photo_path = req.files && req.files['photo'] ? '/uploads/' + req.files['photo'][0].filename : null;

    db.query(
        `INSERT INTO inventories
         (procurement_item_id, item_name, inventory_code, receive_date, room_id, condition_status, qr_code_path, barcode_path, photo_path)
         VALUES (?, ?, ?, ?, ?, 'good', ?, ?, ?)`,
        [
            procurement_item_id || null,
            item_name,
            inventory_code || null,
            receive_date,
            room_id || null,
            qr_code_path,
            barcode_path,
            photo_path,
        ],
        (err, result) => {
            if (err) return res.status(500).json({ error: err.message });
            res.json({ message: 'Inventaris berhasil diterima.', id: result.insertId });
        }
    );
};

// PUT /api/inventories/:id
exports.updateInventory = (req, res) => {
    const { id } = req.params;
    const { inventory_code, receive_date, room_id, condition_status } = req.body;

    const qr_code_path = req.files && req.files['qr_code'] ? '/uploads/' + req.files['qr_code'][0].filename : undefined;
    const barcode_path = req.files && req.files['barcode'] ? '/uploads/' + req.files['barcode'][0].filename : undefined;
    const photo_path = req.files && req.files['photo'] ? '/uploads/' + req.files['photo'][0].filename : undefined;

    // Build dynamic SET clause
    const sets = [];
    const params = [];

    if (inventory_code !== undefined) { sets.push('inventory_code = ?'); params.push(inventory_code); }
    if (receive_date !== undefined) { sets.push('receive_date = ?'); params.push(receive_date); }
    if (room_id !== undefined) { sets.push('room_id = ?'); params.push(room_id || null); }
    if (condition_status !== undefined) { sets.push('condition_status = ?'); params.push(condition_status); }
    if (qr_code_path !== undefined) { sets.push('qr_code_path = ?'); params.push(qr_code_path); }
    if (barcode_path !== undefined) { sets.push('barcode_path = ?'); params.push(barcode_path); }
    if (photo_path !== undefined) { sets.push('photo_path = ?'); params.push(photo_path); }

    if (sets.length === 0) {
        return res.status(422).json({ error: 'Tidak ada data untuk diperbarui.' });
    }

    params.push(id);
    db.query(`UPDATE inventories SET ${sets.join(', ')} WHERE id = ?`, params, (err) => {
        if (err) return res.status(500).json({ error: err.message });
        res.json({ message: 'Inventaris berhasil diperbarui.' });
    });
};
