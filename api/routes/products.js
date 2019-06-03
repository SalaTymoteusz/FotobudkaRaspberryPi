const express = require("express");
const router = express.Router();
const mongoose = require("mongoose");
const multer = require('multer');

const storage = multer.diskStorage({
  destination: function(req, file, cb) {
    cb(null, './uploads/');
  },
  filename: function(req, file, cb) {
    cb(null, file.originalname);
  }
});

const fileFilter = (req, file, cb) => {
  // reject a file
  if (file.mimetype === 'image/jpeg' || file.mimetype === 'image/png') {
    cb(null, true);
  } else {
    cb(null, false);
  }
};

const upload = multer({
  storage: storage,
  fileFilter: fileFilter
});

const Product = require("../models/product");

router.get("/", (req, res, next) => {
  Product.find()
    .select("code Image albumID")
    .exec()
    .then(docs => {
      const response = {
        count: docs.length,
        products: docs.map(doc => {
          return {
            albumID: doc.albumID,
            code: doc.code,
            Image: "https://fotobudka.herokuapp.com/" + doc.Image,
            request: {
              type: "GET",
              url: "https://fotobudka.herokuapp.com/products/" + doc.code
            }
          };
        })
      };
      //   if (docs.length >= 0) {
      res.status(200).json(response);
      //   } else {
      //       res.status(404).json({
      //           message: 'No entries found'
      //       });
      //   }
    })
    .catch(err => {
      console.log(err);
      res.status(500).json({
        error: err
      });
    });
});

router.post("/", upload.single('Image'), (req, res, next) => {
  const product = new Product({
    albumID: req.body.albumID,
    code: req.body.code,
    Image: req.file.path 
  });
  product
    .save()
    .then(result => {
      console.log(result);
      res.status(201).json({
        message: "Created post successfully",
        createdProduct: {
            albumID: result.albumID,
            code: result.code,
            Image: "https://fotobudka.herokuapp.com/" + result.Image,
            request: {
                type: 'GET',
                url: "https://fotobudka.herokuapp.com/products/" + result.code
            }
        }
      });
    })
    .catch(err => {
      console.log(err);
      res.status(500).json({
        error: err
      });
    });
});

router.get("/:code", (req, res, next) => {
  //const id = req.params._id;
  var codeGet = {code: req.params.code}
  Product.find(codeGet)
    .select('code Image albumID')
    .exec()
    .then(doc => {
      console.log("From database", doc);
      if (doc) {
        res.status(200).json(doc);
      } else {
        res
          .status(404)
          .json({ message: "No valid entry found for provided ID" });
      }
    })
    .catch(err => {
      console.log(err);
      res.status(500).json({ error: err });
    });
});


module.exports = router;