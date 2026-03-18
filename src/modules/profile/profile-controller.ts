import { Request, Response } from 'express';
import { ProfileService } from './profile-service';

export class ProfileController {
    private authService: ProfileService;

    constructor() {
        this.authService = new ProfileService();
    }
    /*Example:
    async getProfile(req: Request, res: Response): Promise<void> {
        try {
            const userId = req.session.userId;
            if (!userId) {
                res.status(401).json({ message: 'Unauthorized' });
                return;
            }
            const profile = await this.authService.getProfile(userId);
            res.json(profile);
        } catch (error) {
            res.status(500).json({ message: 'Error retrieving profile', error });
        }
    }*/
}