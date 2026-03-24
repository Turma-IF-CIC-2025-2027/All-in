import "reflect-metadata";
import dotenv from 'dotenv';
dotenv.config();

import express from 'express';
import cookieParser from 'cookie-parser';
import path from 'path';
import cors from 'cors';
import helmet from 'helmet';
import rateLimit from 'express-rate-limit';
import allRoutes from './src/modules/router';

const app = express();

//Global Middlewares
app.use(helmet());
app.use(cors({
    origin: process.env.CORS_ORIGIN || 'http://localhost:80',
    credentials: true
}));
app.use(rateLimit({
    windowMs: 15 * 60 * 1000,
    max: 100
}));

//Parsing
app.use(cookieParser());
app.use(express.json());
app.use(express.urlencoded({ extended: true }));

//Static Files
app.use('/public', express.static(path.join(__dirname, './public')));

//Routes Injection
app.use('/', allRoutes);

export default app;