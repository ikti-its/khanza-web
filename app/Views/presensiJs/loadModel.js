// const tf = require('@tensorflow/tfjs');
// const fs = require('fs');

// async function convertModel() {
//     // Load the Keras model
//     const kerasModel = await tf.loadLayersModel('file://app/models/facenet_keras.h5');

//     // Convert the Keras model to TensorFlow.js format
//     const tfjsModel = await kerasModel.save('file://facenet_tfjs');

//     // Save the model architecture as JSON
//     fs.writeFileSync('facenet_tfjs/model.json', JSON.stringify(tfjsModel.modelTopology));

//     // Save the model weights as binary files
//     await tfjsModel.weightData.forEachAsync(async (array, i) => {
//         const weights = new Float32Array(await array.data());
//         const filePath = `facenet_tfjs/group${i}.bin`;
//         fs.writeFileSync(filePath, Buffer.from(weights.buffer));
//     });
// }

// convertModel().then(() => {
//     console.log('Model converted successfully.');
// }).catch(error => {
//     console.error('Error converting model:', error);
// });
