import { Entity, PrimaryGeneratedColumn, Column } from "typeorm";

@Entity() // Isto diz ao TypeORM que esta classe é uma tabela no SQL
export class UserEntity {
    @PrimaryGeneratedColumn()
    id: string;

    @Column()
    username: string;

    @Column()
    email: string;

    @Column()
    password: string;

    @Column()
    age: number;
}