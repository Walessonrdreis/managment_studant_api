# Documentação da API

Esta documentação descreve como o frontend pode interagir com a API através dos controladores disponíveis.

## Papéis de Usuário

Os papéis de usuário são identificados pelos seguintes IDs:
- **1**: Admin
- **2**: Teacher
- **3**: Student

## 1. StudentSubjectController

### Descrição
Controlador para gerenciar as disciplinas dos estudantes. Permite listar, matricular, obter detalhes, atualizar e excluir matrículas.

### Endpoints

- **Listar Matrículas**
  - **Método:** GET
  - **URL:** `/api/student-subjects`
  - **Resposta:** Lista de matrículas.

- **Matricular Estudante**
  - **Método:** POST
  - **URL:** `/api/student-subjects`
  - **Corpo da Requisição:**
    ```json
    {
      "student_id": 1,
      "subject_id": 2
    }
    ```
  - **Resposta:** Detalhes da matrícula criada.

- **Obter Detalhes da Matrícula**
  - **Método:** GET
  - **URL:** `/api/student-subjects/{id}`
  - **Resposta:** Detalhes da matrícula.

- **Atualizar Matrícula**
  - **Método:** PUT
  - **URL:** `/api/student-subjects/{id}`
  - **Corpo da Requisição:**
    ```json
    {
      "student_id": 1,
      "subject_id": 2
    }
    ```
  - **Resposta:** Detalhes da matrícula atualizada.

- **Excluir Matrícula**
  - **Método:** DELETE
  - **URL:** `/api/student-subjects/{id}`
  - **Resposta:** Mensagem de sucesso.

## 2. UserController

### Descrição
Controlador para gerenciar usuários. Permite listar, criar, obter detalhes, atualizar e excluir usuários.

### Endpoints

- **Listar Usuários**
  - **Método:** GET
  - **URL:** `/api/users`
  - **Resposta:** Lista de usuários.

- **Criar Usuário**
  - **Método:** POST
  - **URL:** `/api/users`
  - **Corpo da Requisição:**
    ```json
    {
      "name": "Nome do Usuário",
      "email": "email@exemplo.com",
      "password": "senha123",
      "role_id": 1
    }
    ```
  - **Resposta:** Detalhes do usuário criado.

- **Obter Detalhes do Usuário**
  - **Método:** GET
  - **URL:** `/api/users/{id}`
  - **Resposta:** Detalhes do usuário.

- **Atualizar Usuário**
  - **Método:** PUT
  - **URL:** `/api/users/{id}`
  - **Corpo da Requisição:**
    ```json
    {
      "name": "Nome Atualizado",
      "email": "novoemail@exemplo.com"
    }
    ```
  - **Resposta:** Detalhes do usuário atualizado.

- **Excluir Usuário**
  - **Método:** DELETE
  - **URL:** `/api/users/{id}`
  - **Resposta:** Mensagem de sucesso.

## 3. TeacherController

### Descrição
Controlador para gerenciar professores. Permite listar, criar, obter detalhes, atualizar e excluir professores.

### Endpoints

- **Listar Professores**
  - **Método:** GET
  - **URL:** `/api/teachers`
  - **Resposta:** Lista de professores.

- **Criar Professor**
  - **Método:** POST
  - **URL:** `/api/teachers`
  - **Corpo da Requisição:**
    ```json
    {
      "name": "Nome do Professor",
      "subject": "Disciplina",
      "user_id": 1
    }
    ```
  - **Resposta:** Detalhes do professor criado.

- **Obter Detalhes do Professor**
  - **Método:** GET
  - **URL:** `/api/teachers/{id}`
  - **Resposta:** Detalhes do professor.

- **Atualizar Professor**
  - **Método:** PUT
  - **URL:** `/api/teachers/{id}`
  - **Corpo da Requisição:**
    ```json
    {
      "name": "Nome Atualizado",
      "subject": "Nova Disciplina"
    }
    ```
  - **Resposta:** Detalhes do professor atualizado.

- **Excluir Professor**
  - **Método:** DELETE
  - **URL:** `/api/teachers/{id}`
  - **Resposta:** Mensagem de sucesso.

## 4. SubjectController

### Descrição
Controlador para gerenciar disciplinas. Permite listar, criar, obter detalhes, atualizar e excluir disciplinas.

### Endpoints

- **Listar Disciplinas**
  - **Método:** GET
  - **URL:** `/api/subjects`
  - **Resposta:** Lista de disciplinas.

- **Criar Disciplina**
  - **Método:** POST
  - **URL:** `/api/subjects`
  - **Corpo da Requisição:**
    ```json
    {
      "name": "Nome da Disciplina"
    }
    ```
  - **Resposta:** Detalhes da disciplina criada.

- **Obter Detalhes da Disciplina**
  - **Método:** GET
  - **URL:** `/api/subjects/{id}`
  - **Resposta:** Detalhes da disciplina.

- **Atualizar Disciplina**
  - **Método:** PUT
  - **URL:** `/api/subjects/{id}`
  - **Corpo da Requisição:**
    ```json
    {
      "name": "Nome Atualizado"
    }
    ```
  - **Resposta:** Detalhes da disciplina atualizada.

- **Excluir Disciplina**
  - **Método:** DELETE
  - **URL:** `/api/subjects/{id}`
  - **Resposta:** Mensagem de sucesso.

## 5. StudentController

### Descrição
Controlador para gerenciar estudantes. Permite listar, criar, obter detalhes, atualizar e excluir estudantes.

### Endpoints

- **Listar Estudantes**
  - **Método:** GET
  - **URL:** `/api/students`
  - **Resposta:** Lista de estudantes.

- **Criar Estudante**
  - **Método:** POST
  - **URL:** `/api/students`
  - **Corpo da Requisição:**
    ```json
    {
      "name": "Nome do Estudante",
      "email": "email@exemplo.com",
      "date_of_birth": "2000-01-01",
      "user_id": 1
    }
    ```
  - **Resposta:** Detalhes do estudante criado.

- **Obter Detalhes do Estudante**
  - **Método:** GET
  - **URL:** `/api/students/{id}`
  - **Resposta:** Detalhes do estudante.

- **Atualizar Estudante**
  - **Método:** PUT
  - **URL:** `/api/students/{id}`
  - **Corpo da Requisição:**
    ```json
    {
      "name": "Nome Atualizado",
      "email": "novoemail@exemplo.com"
    }
    ```
  - **Resposta:** Detalhes do estudante atualizado.

- **Excluir Estudante**
  - **Método:** DELETE
  - **URL:** `/api/students/{id}`
  - **Resposta:** Mensagem de sucesso.

## Conclusão

Esta documentação fornece uma visão geral de como interagir com a API. Para mais detalhes, consulte a implementação dos controladores.
