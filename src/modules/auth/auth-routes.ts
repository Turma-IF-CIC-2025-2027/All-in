import { Router } from 'express';
import { AuthController } from './auth-controller';

const router = Router();
const authController = new AuthController();

router.get('/login', (req, res) => {
    res.sendFile('login.html', { root: './src/modules/auth/views' });
});

router.get('/register', (req, res) => {
    res.sendFile('registrar.html', { root: './src/modules/auth/views' });
});

router.post('/register', (req, res) => authController.register(req, res));
router.post('/login', (req, res) => authController.login(req, res));
router.post('/logout', (req, res) => authController.logout(req, res));

export { router as AuthRoutes };
