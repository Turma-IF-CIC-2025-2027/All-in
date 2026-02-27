import { Entity, PrimaryGeneratedColumn, Column } from "typeorm";

@Entity() // Isto diz ao TypeORM que esta classe é uma tabela no SQL
export class ExampleGlobal {
    /*
    @PrimaryGeneratedColumn()
    id: number;

    @Column()
    name: string;

    @Column()
    age: number;*/
}
//Global Entities