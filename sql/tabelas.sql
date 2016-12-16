create table empregos(
  id serial,
  id_user bigint not null,
  dia_fechamento tinyint not null default 25,
  porc_normal smallint not null default 50,
  porc_feriados smallint not null default 100,
  nome_emprego varchar(30) not null default "Emprego",
  horario_saida time,
  carga_horaria enum("220","200","180","150") default "220",
  banco_horas Boolean default false,
  notificacoes Boolean default false,
  updt_time timestamp
);

create table horas(
  id serial,
  id_user bigint not null,
  id_emprego bigint not null,
  quantidade smallint not null default 0,
  hora_inicial time default "18:00",
  hora_termino time default "19:00",
  dta Date not null,
  tipo_hora varchar(20),
  updt_time timestamp
);

create table porcentagemdiferenciada(
  id serial,
  id_user bigint not null,  
  id_emprego bigint not null,
  dia_semana tinyint not null default 0,
  porcadicional smallint not null default 100,
  updt_time timestamp
);

create table salarios(
  id serial,
  id_emprego bigint not null,
  id_user bigint not null,
  id_user bigint not null,  
  valorsalario Decimal(15,2) not null default 1000.0,
  status boolean not null default false,
  vigencia date,
  updt_time timestamp
);