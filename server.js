const express = require('express')
const app = express()
const path = require('path')
const port = 3000

app.use('/script', express.static(path.join(__dirname, 'script')))
app.use('/style', express.static(path.join(__dirname, 'style')))
app.use('/image', express.static(path.join(__dirname, 'image')))
app.use('/pages', express.static(path.join(__dirname, 'pages')))
app.use('/item-shop', express.static(path.join(__dirname, 'item-shop')))
app.use('/main.html', express.static(path.join(__dirname, 'main.html')))

app.get('/', (req, res) => {
    res.sendFile(__dirname + '/main.html')
})

app.listen(port, () => {
    console.log(`Example app listening at http://localhost:${port}`)
})