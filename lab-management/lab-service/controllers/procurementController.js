const db = require('../config/db');

// ========================
// DRAFTS
// ========================

// GET /api/procurement/drafts
// Optional query: ?created_by=&status=
exports.indexDrafts = (req, res) => {
    let query = `
        SELECT d.*, u.name AS creator_name,
               (SELECT COUNT(*) FROM procurement_items WHERE draft_id = d.id) AS item_count
        FROM procurement_drafts d
        LEFT JOIN users u ON u.id = d.created_by
    `;
    const conditions = [];
    const params = [];

    if (req.query.created_by) {
        conditions.push('d.created_by = ?');
        params.push(req.query.created_by);
    }
    if (req.query.status) {
        conditions.push('d.status = ?');
        params.push(req.query.status);
    }

    if (conditions.length) {
        query += ' WHERE ' + conditions.join(' AND ');
    }
    query += ' ORDER BY d.updated_at DESC';

    db.query(query, params, (err, results) => {
        if (err) return res.status(500).json({ error: err.message });
        res.json(results);
    });
};

// GET /api/procurement/drafts/locked
exports.lockedDrafts = (req, res) => {
    const query = `
        SELECT d.*, u.name AS creator_name,
               (SELECT COUNT(*) FROM procurement_items WHERE draft_id = d.id) AS item_count
        FROM procurement_drafts d
        LEFT JOIN users u ON u.id = d.created_by
        WHERE d.status = 'locked'
        ORDER BY d.updated_at DESC
    `;
    db.query(query, (err, results) => {
        if (err) return res.status(500).json({ error: err.message });
        res.json(results);
    });
};

// GET /api/procurement/drafts/finalized
exports.finalizedDrafts = (req, res) => {
    const query = `
        SELECT d.*, u.name AS creator_name,
               (SELECT COUNT(*) FROM procurement_items WHERE draft_id = d.id) AS item_count
        FROM procurement_drafts d
        LEFT JOIN users u ON u.id = d.created_by
        WHERE d.status = 'finalized'
        ORDER BY d.updated_at DESC
    `;
    db.query(query, (err, results) => {
        if (err) return res.status(500).json({ error: err.message });
        res.json(results);
    });
};

// POST /api/procurement/drafts/
exports.storeDraft = (req, res) => {
    const { title, year, created_by } = req.body;
    if (!title || !year || !created_by) {
        return res.status(422).json({ error: 'Title, year, dan created_by wajib diisi.' });
    }

    db.query(
        'INSERT INTO procurement_drafts (title, year, created_by) VALUES (?, ?, ?)',
        [title, year, created_by],
        (err, result) => {
            if (err) return res.status(500).json({ error: err.message });
            res.json({ message: 'Draf berhasil dibuat', id: result.insertId });
        }
    );
};

// GET /api/procurement/drafts/:id
exports.showDraft = (req, res) => {
    const { id } = req.params;
    db.query(
        `SELECT d.*, u.name AS creator_name
         FROM procurement_drafts d
         LEFT JOIN users u ON u.id = d.created_by
         WHERE d.id = ?`,
        [id],
        (err, drafts) => {
            if (err) return res.status(500).json({ error: err.message });
            if (!drafts.length) return res.status(404).json({ error: 'Draf tidak ditemukan.' });

            const draft = drafts[0];
            db.query(
                'SELECT pi.*, i.item_name AS replaced_inventory_name FROM procurement_items pi LEFT JOIN inventories i ON i.id = pi.replaced_inventory_id WHERE pi.draft_id = ? ORDER BY pi.id ASC',
                [id],
                (err, items) => {
                    if (err) return res.status(500).json({ error: err.message });
                    draft.items = items;
                    res.json(draft);
                }
            );
        }
    );
};

// PUT /api/procurement/drafts/:id
exports.updateDraft = (req, res) => {
    const { id } = req.params;
    const { title, year } = req.body;

    db.query('SELECT status FROM procurement_drafts WHERE id = ?', [id], (err, rows) => {
        if (err) return res.status(500).json({ error: err.message });
        if (!rows.length) return res.status(404).json({ error: 'Draf tidak ditemukan.' });

        const status = rows[0].status;
        if (status !== 'draft') {
            return res.status(403).json({ error: `Draf berstatus '${status}' tidak dapat diubah.` });
        }

        db.query(
            'UPDATE procurement_drafts SET title = ?, year = ? WHERE id = ?',
            [title, year, id],
            (err) => {
                if (err) return res.status(500).json({ error: err.message });
                res.json({ message: 'Draf berhasil diperbarui.' });
            }
        );
    });
};

// DELETE /api/procurement/drafts/:id
exports.destroyDraft = (req, res) => {
    const { id } = req.params;

    db.query('SELECT status FROM procurement_drafts WHERE id = ?', [id], (err, rows) => {
        if (err) return res.status(500).json({ error: err.message });
        if (!rows.length) return res.status(404).json({ error: 'Draf tidak ditemukan.' });

        const status = rows[0].status;
        if (status !== 'draft') {
            return res.status(403).json({ error: `Draf berstatus '${status}' tidak dapat dihapus.` });
        }

        db.query('DELETE FROM procurement_drafts WHERE id = ?', [id], (err) => {
            if (err) return res.status(500).json({ error: err.message });
            res.json({ message: 'Draf berhasil dihapus.' });
        });
    });
};

// POST /api/procurement/drafts/:id/lock
exports.lockDraft = (req, res) => {
    const { id } = req.params;

    db.query('SELECT status FROM procurement_drafts WHERE id = ?', [id], (err, rows) => {
        if (err) return res.status(500).json({ error: err.message });
        if (!rows.length) return res.status(404).json({ error: 'Draf tidak ditemukan.' });

        if (rows[0].status !== 'draft') {
            return res.status(403).json({ error: `Hanya draf berstatus 'draft' yang bisa dikunci.` });
        }

        // Check if draft has items
        db.query('SELECT COUNT(*) AS cnt FROM procurement_items WHERE draft_id = ?', [id], (err, countRows) => {
            if (err) return res.status(500).json({ error: err.message });
            if (countRows[0].cnt === 0) {
                return res.status(422).json({ error: 'Draf harus memiliki minimal 1 item sebelum dikunci.' });
            }

            db.query(
                "UPDATE procurement_drafts SET status = 'locked' WHERE id = ?",
                [id],
                (err) => {
                    if (err) return res.status(500).json({ error: err.message });
                    res.json({ message: 'Draf berhasil dikunci.' });
                }
            );
        });
    });
};

// POST /api/procurement/drafts/:id/finalize
exports.finalizeDraft = (req, res) => {
    const { id } = req.params;

    db.query('SELECT status FROM procurement_drafts WHERE id = ?', [id], (err, rows) => {
        if (err) return res.status(500).json({ error: err.message });
        if (!rows.length) return res.status(404).json({ error: 'Draf tidak ditemukan.' });

        if (rows[0].status !== 'locked') {
            return res.status(403).json({ error: `Hanya draf berstatus 'locked' yang bisa difinalisasi.` });
        }

        db.query(
            "UPDATE procurement_drafts SET status = 'finalized' WHERE id = ?",
            [id],
            (err) => {
                if (err) return res.status(500).json({ error: err.message });
                res.json({ message: 'Draf berhasil difinalisasi.' });
            }
        );
    });
};

// ========================
// ITEMS
// ========================

// POST /api/procurement/drafts/:id/items
exports.storeItem = (req, res) => {
    const draftId = req.params.id;
    const { item_type, item_name, quantity, estimated_price, purchase_link, notes, replaced_inventory_id } = req.body;

    if (!item_type || !item_name || !quantity || estimated_price === undefined) {
        return res.status(422).json({ error: 'item_type, item_name, quantity, dan estimated_price wajib diisi.' });
    }

    db.query('SELECT status FROM procurement_drafts WHERE id = ?', [draftId], (err, rows) => {
        if (err) return res.status(500).json({ error: err.message });
        if (!rows.length) return res.status(404).json({ error: 'Draf tidak ditemukan.' });

        if (rows[0].status !== 'draft') {
            return res.status(403).json({ error: `Tidak dapat menambah item pada draf berstatus '${rows[0].status}'.` });
        }

        db.query(
            `INSERT INTO procurement_items (draft_id, item_type, item_name, quantity, estimated_price, purchase_link, notes, replaced_inventory_id)
             VALUES (?, ?, ?, ?, ?, ?, ?, ?)`,
            [draftId, item_type, item_name, quantity, estimated_price, purchase_link || null, notes || null, replaced_inventory_id || null],
            (err, result) => {
                if (err) return res.status(500).json({ error: err.message });
                res.json({ message: 'Item berhasil ditambahkan.', id: result.insertId });
            }
        );
    });
};

// PUT /api/procurement/items/:id
exports.updateItem = (req, res) => {
    const { id } = req.params;
    const { item_type, item_name, quantity, estimated_price, purchase_link, notes, replaced_inventory_id } = req.body;

    // Get the item's draft status
    db.query(
        `SELECT pi.*, pd.status AS draft_status
         FROM procurement_items pi
         JOIN procurement_drafts pd ON pd.id = pi.draft_id
         WHERE pi.id = ?`,
        [id],
        (err, rows) => {
            if (err) return res.status(500).json({ error: err.message });
            if (!rows.length) return res.status(404).json({ error: 'Item tidak ditemukan.' });

            if (rows[0].draft_status !== 'draft') {
                return res.status(403).json({ error: `Tidak dapat mengubah item pada draf berstatus '${rows[0].draft_status}'.` });
            }

            db.query(
                `UPDATE procurement_items
                 SET item_type = ?, item_name = ?, quantity = ?, estimated_price = ?, purchase_link = ?, notes = ?, replaced_inventory_id = ?
                 WHERE id = ?`,
                [item_type, item_name, quantity, estimated_price, purchase_link || null, notes || null, replaced_inventory_id || null, id],
                (err) => {
                    if (err) return res.status(500).json({ error: err.message });
                    res.json({ message: 'Item berhasil diperbarui.' });
                }
            );
        }
    );
};

// DELETE /api/procurement/items/:id
exports.destroyItem = (req, res) => {
    const { id } = req.params;

    db.query(
        `SELECT pi.*, pd.status AS draft_status
         FROM procurement_items pi
         JOIN procurement_drafts pd ON pd.id = pi.draft_id
         WHERE pi.id = ?`,
        [id],
        (err, rows) => {
            if (err) return res.status(500).json({ error: err.message });
            if (!rows.length) return res.status(404).json({ error: 'Item tidak ditemukan.' });

            if (rows[0].draft_status !== 'draft') {
                return res.status(403).json({ error: `Tidak dapat menghapus item pada draf berstatus '${rows[0].draft_status}'.` });
            }

            db.query('DELETE FROM procurement_items WHERE id = ?', [id], (err) => {
                if (err) return res.status(500).json({ error: err.message });
                res.json({ message: 'Item berhasil dihapus.' });
            });
        }
    );
};

// ========================
// REVIEW (Kaprodi)
// ========================

// PUT /api/procurement/items/:id/approve
exports.approveItem = (req, res) => {
    const { id } = req.params;

    db.query(
        `SELECT pi.*, pd.status AS draft_status
         FROM procurement_items pi
         JOIN procurement_drafts pd ON pd.id = pi.draft_id
         WHERE pi.id = ?`,
        [id],
        (err, rows) => {
            if (err) return res.status(500).json({ error: err.message });
            if (!rows.length) return res.status(404).json({ error: 'Item tidak ditemukan.' });

            if (rows[0].draft_status !== 'locked') {
                return res.status(403).json({ error: 'Item hanya bisa di-approve pada draf berstatus locked.' });
            }

            db.query(
                "UPDATE procurement_items SET approved_status = 'approved' WHERE id = ?",
                [id],
                (err) => {
                    if (err) return res.status(500).json({ error: err.message });
                    res.json({ message: 'Item disetujui.' });
                }
            );
        }
    );
};

// PUT /api/procurement/items/:id/reject
exports.rejectItem = (req, res) => {
    const { id } = req.params;

    db.query(
        `SELECT pi.*, pd.status AS draft_status
         FROM procurement_items pi
         JOIN procurement_drafts pd ON pd.id = pi.draft_id
         WHERE pi.id = ?`,
        [id],
        (err, rows) => {
            if (err) return res.status(500).json({ error: err.message });
            if (!rows.length) return res.status(404).json({ error: 'Item tidak ditemukan.' });

            if (rows[0].draft_status !== 'locked') {
                return res.status(403).json({ error: 'Item hanya bisa di-reject pada draf berstatus locked.' });
            }

            db.query(
                "UPDATE procurement_items SET approved_status = 'rejected' WHERE id = ?",
                [id],
                (err) => {
                    if (err) return res.status(500).json({ error: err.message });
                    res.json({ message: 'Item ditolak.' });
                }
            );
        }
    );
};
