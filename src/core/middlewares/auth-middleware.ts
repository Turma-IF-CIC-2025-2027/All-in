import { Request, Response, NextFunction } from 'express';
import { UserPayload } from '../../types/express';
import jwt from 'jsonwebtoken';



export const authMiddleware = (req: Request, res: Response, next: NextFunction):void => {
    const token = req.cookies?.token || req.headers['authorization']?.split(' ')[1];

    if (!token) {
      res.status(401).json({ erro: 'Token is missing. Access denied.' });
      return;
    }

    try {
        const decoded = jwt.verify(token, process.env.JWT_SECRET as string) as UserPayload;
        req.user = decoded;
        next();
    } catch (err: any) {
        res.status(403).json({ erro: 'Token inválido ou expirado.' });
    }
};

