import { Router } from 'express';
import { AuthRoutes } from './auth/auth-routes';

const router = Router();

router.use('/auth', AuthRoutes);

router.get('/', (req, res) => {
    res.send("Hello World!");
});


export default router;