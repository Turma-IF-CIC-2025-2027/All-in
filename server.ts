import "reflect-metadata";
import express from 'express';
import path from 'path';
import cors from 'cors';
import { AppDataSource } from './src/core/database/data-source'; //TypeORM config
//import allRoutes from './modules/router'; //Routes

const app = express();
const PORT = process.env.PORT || 8080;

//Global Middlewares
app.use(cors());  
app.use(express.json()); 
app.use(express.urlencoded({ extended: true }));

//Add Static Files
app.use(express.static(path.join(__dirname, '../public')));

//Routes Injection
//app.use('/', allRoutes);

//Initialize BD
/*AppDataSource.initialize()
    .then(() => {
        console.log("Database Connected");
        
        app.listen(PORT, () => {
            console.log(`The server is running on: http://localhost:${PORT}`);
        });
    })
    .catch((error:any) => {
        console.error("Error connecting to the database", error);
    });*/

app.get('/', (req, res) => {
    res.send("Hello World!");
});

//Remove after this comment, just for testing while there is no DB
app.listen(PORT, () => {
            console.log(`The server is running on: http://localhost:${PORT}`);
        });