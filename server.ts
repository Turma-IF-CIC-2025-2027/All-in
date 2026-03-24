import app from './app';
import { AppDataSource } from './src/core/database/data-source'; //TypeORM config

const PORT = process.env.PORT || 80;

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
        process.exit(1);
    });*/

//Remove after this comment, just for testing while there is no DB
app.listen(PORT, () => {
            console.log(`The server is running on: http://localhost:${PORT}`);
        });