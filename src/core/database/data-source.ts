import "reflect-metadata";
import { DataSource } from "typeorm";
import dotenv from "dotenv";
dotenv.config();

export const AppDataSource = new DataSource({
    type: "mysql", 
    host: process.env.DB_HOST || "localhost",
    port: Number(process.env.DB_PORT) || 3306,
    username: process.env.DB_USER || "root",
    password: process.env.DB_PASSWORD || "",
    database: process.env.DB_NAME || "all_in_db",
    timezone: "Z", //UTC timezone
    
    synchronize: true, //Just for developing(creates the tables automaticly)
    logging: true,    //Shows the queries
    
    entities: [//In Production change to .js files
        "src/core/database/entities/*.ts",
        "src/modules/**/entities/*.ts"    
    ],
    
    migrations: ["src/core/database/migrations/*.ts"],
    
    subscribers: [
        "src/core/database/subscribers/*.ts",
        "src/modules/**/subscribers/*.ts" 
    ],
});