const express = require('express');
const router = express.Router();

const {
    index,
    store,
    update,
    destroy,
} = require('../controllers/bhpController');

router.get('/', index);
router.post('/', store);
router.put('/:id', update);
router.delete('/:id', destroy);

module.exports = router;
