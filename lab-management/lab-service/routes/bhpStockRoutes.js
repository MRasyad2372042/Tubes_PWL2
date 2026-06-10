const express = require('express');
const router = express.Router();

const {
    pendingBhpItems,
    receiveBhp,
    usageBhp,
    addStock,
    stockLogs,
    stockLogsByItem,
    allBhpItems,
} = require('../controllers/bhpStockController');

router.get('/pending', pendingBhpItems);
router.get('/items', allBhpItems);
router.post('/receive', receiveBhp);
router.post('/usage', usageBhp);
router.post('/add', addStock);
router.get('/logs', stockLogs);
router.get('/logs/:bhpItemId', stockLogsByItem);

module.exports = router;
