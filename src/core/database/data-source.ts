import "reflect-metadata";
import { DataSource } from "typeorm";
import dotenv from "dotenv";

//Not created yet
dotenv.config();

export const AppDataSource = new DataSource({
    type: "mysql", //postgres,sqlite,etc...
    host: process.env.DB_HOST || "localhost",
    port: Number(process.env.DB_PORT) || 3306,
    username: process.env.DB_USER || "root",
    password: process.env.DB_PASSWORD || "",
    database: process.env.DB_NAME || "all_in_db",
    
    synchronize: true, // Just for developing(creates the tables automaticly)
    logging: true,    //Shows the queries
    
    entities: [
        "src/core/database/entities/*.ts",
        "src/modules/**/entities/*.ts"    
    ],
    
    migrations: ["src/core/database/migrations/*.ts"],
    
    subscribers: [],
});