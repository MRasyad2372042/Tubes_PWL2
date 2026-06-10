const express = require('express');
const router = express.Router();
const multer = require('multer');
const path = require('path');

// Multer storage config
const storage = multer.diskStorage({
    destination: (req, file, cb) => {
        cb(null, path.join(__dirname, '..', 'uploads'));
    },
    filename: (req, file, cb) => {
        const uniqueSuffix = Date.now() + '-' + Math.round(Math.random() * 1E9);
        cb(null, uniqueSuffix + path.extname(file.originalname));
    },
});

const upload = multer({
    storage,
    limits: { fileSize: 5 * 1024 * 1024 }, // 5MB
    fileFilter: (req, file, cb) => {
        const allowed = /jpeg|jpg|png|gif|webp|svg/;
        const ext = allowed.test(path.extname(file.originalname).toLowerCase());
        const mime = allowed.test(file.mimetype);
        if (ext && mime) return cb(null, true);
        cb(new Error('Hanya file gambar yang diizinkan.'));
    },
});

const uploadFields = upload.fields([
    { name: 'qr_code', maxCount: 1 },
    { name: 'barcode', maxCount: 1 },
    { name: 'photo', maxCount: 1 },
]);

const {
    pendingItems,
    index,
    receiveItem,
    updateInventory,
} = require('../controllers/inventoryController');

router.get('/pending', pendingItems);
router.get('/', index);
router.post('/receive', uploadFields, receiveItem);
router.put('/:id', uploadFields, updateInventory);

module.exports = router;
