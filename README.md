# Akana Framework

Akana is a PHP framework to create restful API easily and fast.

## TODO

### Pending

- [ ] delete many to many columns
- [ ] Respect dependecy order while generate code for exporting db
- [ ] Command management

### Others

- [ ] using Schema while fetch data
- [ ] JWT and Authentication
- [ ] export_db command option: structure - data
- [ ] Annotion engine (Route(endpoint), Secure())
- [ ] Route annotation
- [ ] rebuild migrations system
- [ ] exceptions
- [ ] warning before execute migrations instructions
- [ ] what happen if user delete a model or field link with other delete
- [ ] reserve some table name "junction_table"
- [ ] Avoid using alias many time in many to many relation
- [ ] Migration
  - [ ] Update table
    - [ ] delete column
    - [ ] add a column
    - [ ] rename field
  - [ ] Rename table
  - [ ] Change details for Field
  - [ ] Track migrations history (transition, migrate all)
  - [ ] safety for drop command (save state of table)
  - [ ] Check if columns on orm is empty
- [ ] Add command add_resource
- [ ] Errors handle
  - [ ] Check if there is not resource
  - [ ] NotFoundException
- [ ] borderCheck
  - [ ] check if every registred resources has it folder then create it
  - [ ] every tables file must have a namespace (correct)
- [ ] dev and prod mod
  - [ ] hide pdo exception message in prod mode
  - [ ] forbidden migrate command in prod mode
- [ ]  Solve cross orgin bug
- [ ] forbidden to add id column in models
- [ ] update ORM::typing method
- [ ] choice field
- [ ] bidirection relation
- [ ] Create many controller files
- [ ] Create many models files
- [ ] security
- [ ] documentation
- [ ] cli console
- [ ] on checl type emprove json column type validation
- [ ] route alias
- [ ] other database connectivity
  - [ ] use postgresql database
  - [ ] use sqlite database
  - [ ] use mangoDB database
