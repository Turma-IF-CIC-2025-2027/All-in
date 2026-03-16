import { Router } from 'express';
import { AuthRoutes } from './auth/auth-routes';
import { ProfileRoutes } from './profile/profile-routes';

const router = Router();

router.use('/auth', AuthRoutes);
router.use('/profile', ProfileRoutes);

router.get('/', (req, res) => {
    res.send("Hello World!");
});


export default router;