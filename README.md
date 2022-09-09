# Akana Framework

Akana is a PHP framework to create restful API easily and fast.

## TODO

### Important

- [x] Migration
  - [x] Create a table for a model
  - [x] Delete a table
  - [ ] Update table
    - [ ] delete column
    - [ ] add a column
    - [ ] rename field
  - [ ] Rename table
  - [ ] Change details for Field
  - [ ] One-to-one Relation
  - [ ] One-to-many Relation
  - [ ] Many-to-many Relation
  - [ ] Track migrations history (transition, migrate all)
  - [ ] warning before execute migrations instructions
  - [ ] safety for drop command (save state of table)
- [ ] Add command add_resource
- [ ] Errors handle
  - [ ] Check if there is not resource
  - [ ] NotFoundException

- [ ] JWT and Authentication
- [ ] dev and prod mod
  - [ ] hide pdo exception message in prod mode
  - [ ] forbidden migrate command in prod mode
- [ ]  Solve cross orgin bug
- [ ] Route annotation
- [ ] Get created object after insertion into table
- [ ] forbidden to add id column in models
- [ ] update ORM::typing method

### No important

- [ ] Do validation before insert and update data into table (schema)
- [ ] Command options management
- [ ] Create many controller files
- [ ] Create many models files
- [ ] security
- [ ] documentation
- [ ] other database connectivity
  - [ ] use postgresql database
  - [ ] use sqlite database
  - [ ] use mangoDB database
