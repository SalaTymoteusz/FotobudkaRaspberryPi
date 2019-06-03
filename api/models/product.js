const mongoose = require('mongoose');

const productSchema = mongoose.Schema({
    code: { type: String, required: true },
    Image: { type: String, required: true },
    albumID: { type: String, required: true}
});

module.exports = mongoose.model('Product', productSchema);