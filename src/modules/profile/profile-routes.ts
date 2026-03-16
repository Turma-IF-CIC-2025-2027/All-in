import { Router } from 'express';
import { ProfileController } from './profile-controller';

const router = Router();
const profileController = new ProfileController();

//router.get('/me', ProfileMiddleware, (req, res) => profileController.getProfile(req, res));

export { router as ProfileRoutes };
