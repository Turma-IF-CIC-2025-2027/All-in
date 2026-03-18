import { Request, Response } from 'express';
import { AuthService } from './auth-service';
import jwt, { SignOptions } from 'jsonwebtoken';
import { UserEntity } from './entities/UserEntity';

const COOKIE_OPTIONS = {
    httpOnly: true,   
    secure: false,  
    sameSite: 'strict' as const,  
    maxAge: 2 * 60 * 60 * 1000  
};

export class AuthController {
    private authService: AuthService;

    constructor() {
        this.authService = new AuthService();
    }

    private generateToken(user: UserEntity): string {
        const options: SignOptions = {
            expiresIn: (process.env.JWT_EXPIRES_IN || '2h') as SignOptions['expiresIn']
        };
        return jwt.sign(
            { id: user.id, email: user.email },
            process.env.JWT_SECRET as string,
            options
        );
    }

    async register(req: Request, res: Response): Promise<void> {
        try {
            const { email, password } = req.body;

            const user = await this.authService.register(email, password);

            res.cookie('token', this.generateToken(user), COOKIE_OPTIONS);
            res.status(201).json({ message: 'User registered successfully'});
        } catch (error:any) {
            res.status(500).json({ message: 'Registration failed', error: error.message });
        }
    }

    async login(req: Request, res: Response): Promise<void> {
        try {
            const { email, password } = req.body;

            const user = await this.authService.login(email, password);

            res.cookie('token', this.generateToken(user), COOKIE_OPTIONS);
            res.status(200).json({ message: 'Login successful'});
        } catch (error:any) {
            res.status(401).json({ message: 'Login failed', error: error.message });
        }
    }

    async logout(_req: Request, res: Response): Promise<void> {
        res.clearCookie('token');
        res.status(200).json({ message: 'Logout successful' });
    }
}