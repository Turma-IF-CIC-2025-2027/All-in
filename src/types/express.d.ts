import { JwtPayload } from 'jsonwebtoken';

export interface UserPayload extends JwtPayload {
  id: number;
  name: string;
}

declare global {
  namespace Express {
    interface Request {
      user?: UserPayload;
    }
  }
}