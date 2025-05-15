#!/bin/zsh

set -a
source .env
set +a

MIGRATIONS_DIR="./migrations"

if [ ! -d "$MIGRATIONS_DIR" ]; then
  echo "Le dossier des migrations n'existe pas : $MIGRATIONS_DIR"
  exit 1
fi

for file in "$MIGRATIONS_DIR"/*.sql; do
  echo "\nMigration : $file"

  table_name=$(grep -iE 'CREATE TABLE IF NOT EXISTS|CREATE TABLE' "$file" | sed -E 's/.*CREATE TABLE (IF NOT EXISTS)?\s*`?([a-zA-Z0-9_]+)`?.*/\2/I' | head -n 1)

  if [ -z "$table_name" ]; then
    echo "Impossible de déterminer le nom de la table pour : $file"
    continue
  fi

  TABLE_EXISTS=$(mysql -h"$DB_HOST" -u"$DB_USER" -p"$DB_PASS" -D"$DB_NAME" -sse "SHOW TABLES LIKE '$table_name';")

  if [ "$TABLE_EXISTS" = "$table_name" ]; then
    echo "Table '$table_name' déjà existante. Migration ignorée."
  else
    echo "Création de la table '$table_name'..."

    mysql -h"$DB_HOST" -u"$DB_USER" -p"$DB_PASS" "$DB_NAME" < "$file"

    if [ $? -eq 0 ]; then
      echo "Migration réussie : $table_name"
    else
      echo "Échec de la migration : $table_name"
    fi
  fi
done
