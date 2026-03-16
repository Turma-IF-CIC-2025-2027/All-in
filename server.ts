import "reflect-metadata";
import express from 'express';
import path from 'path';
import cors from 'cors';
import rateLimit from 'express-rate-limit';
import { AppDataSource } from './src/core/database/data-source'; //TypeORM config
import allRoutes from './src/modules/router';


const app = express();
const PORT = process.env.PORT || 8080;

//Global Middlewares
app.use(cors());  
app.use(express.json()); 
app.use(express.urlencoded({ extended: true }));
app.use(rateLimit({//100 requests per 15 min
    windowMs: 15 * 60 * 1000,
    max: 100 
}));

//Add Static Files
app.use('/public', express.static(path.join(__dirname, './public')));

//Routes Injection
app.use('/', allRoutes);

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

//Remove after this comment, just for testing while there is no DB
app.listen(PORT, () => {
            console.log(`The server is running on: http://localhost:${PORT}`);
        });