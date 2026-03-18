import { Router } from 'express';
import { AuthRoutes } from './auth/auth-routes';
import { ProfileRoutes } from './profile/profile-routes';
import { authMiddleware } from '../core/middlewares/auth-middleware';

const router = Router();

//Public Routes
router.use('/auth', AuthRoutes);
router.get('/', (req, res) => {
    res.send("<h1>Hello World!</h1><a href='/auth/login'>Login here</a> | <a href='/auth/register'>Register here</a>");
});

//Protected Routes
router.use(authMiddleware);
router.use('/profile', ProfileRoutes);

export default router;