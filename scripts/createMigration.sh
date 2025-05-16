#!/bin/zsh

# Vérifie qu’un nom a été fourni
if [ -z "$1" ]; then
  echo " Veuillez fournir le nom de la table."
  echo "Exemple : ./scripts/createMigration.zsh users"
  exit 1
fi

# Variables
TABLE_NAME=$1
TIMESTAMP=$(date +"%Y%m%d_%H%M%S")
FILENAME="${TIMESTAMP}_create_${TABLE_NAME}_table.sql"
MIGRATIONS_DIR="./migrations"
LOG_FILE="./migration.log"
FULL_PATH="${MIGRATIONS_DIR}/${FILENAME}"

# Crée le dossier s’il n’existe pas
mkdir -p "$MIGRATIONS_DIR"

# Crée le fichier de migration SQL
cat <<EOF > "$FULL_PATH"
-- Migration pour créer la table ${TABLE_NAME}
CREATE TABLE IF NOT EXISTS ${TABLE_NAME} (
    id INT AUTO_INCREMENT PRIMARY KEY,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
EOF

# Enregistre le chemin dans le fichier log
echo "$FULL_PATH" >> "$LOG_FILE"

echo "Fichier de migration créé : $FULL_PATH"
