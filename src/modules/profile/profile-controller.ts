import { Request, Response } from 'express';
import { ProfileService } from './profile-service';

export class ProfileController {
    private authService: ProfileService;

    constructor() {
        this.authService = new ProfileService();
    }
}