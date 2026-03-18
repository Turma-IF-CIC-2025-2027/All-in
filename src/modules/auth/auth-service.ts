import bcrypt from 'bcryptjs';
import { UserEntity } from "./entities/UserEntity";

export class AuthService {
    async register(email: string, password: string): Promise<UserEntity> {
        //const hashedPassword = await bcrypt.hash(password, 10);

        return new UserEntity(); //Placeholder return
    }

    async login(email: string, password: string): Promise<UserEntity> {
        //const isPasswordValid = await bcrypt.compare(password, user.password);

        return new UserEntity(); //Placeholder return
    }

}