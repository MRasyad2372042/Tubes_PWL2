const express = require('express');
const router = express.Router();

const {
    indexDrafts,
    lockedDrafts,
    finalizedDrafts,
    storeDraft,
    showDraft,
    updateDraft,
    destroyDraft,
    lockDraft,
    finalizeDraft,
    storeItem,
    updateItem,
    destroyItem,
    approveItem,
    rejectItem,
} = require('../controllers/procurementController');

// Drafts
router.get('/drafts', indexDrafts);
router.get('/drafts/locked', lockedDrafts);
router.get('/drafts/finalized', finalizedDrafts);
router.post('/drafts', storeDraft);
router.get('/drafts/:id', showDraft);
router.put('/drafts/:id', updateDraft);
router.delete('/drafts/:id', destroyDraft);
router.post('/drafts/:id/lock', lockDraft);
router.post('/drafts/:id/finalize', finalizeDraft);

// Items
router.post('/drafts/:id/items', storeItem);
router.put('/items/:id', updateItem);
router.delete('/items/:id', destroyItem);
router.put('/items/:id/approve', approveItem);
router.put('/items/:id/reject', rejectItem);

module.exports = router;
