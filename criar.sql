create table authenticated_user (
  id integer PRIMARY KEY, 
  name text NOT NULL, 
  email text NOT NULL, 
  birth_date date NOT NULL CHECK (CURRENT_DATE - birth_date >= 12), 
  description text, 
  password text NOT NULL, 
  avatar text, 
  city text, 
  is_suspended boolean NOT NULL,
  coutry_code text REFERENCES Country(code)
  );
		