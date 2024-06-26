#!/bin/bash

# Directory to store the keys
KEYS_DIR="/var/www/keys"

# Generate JWT keys
generate_keys() {
    mkdir -p "$KEYS_DIR"

    local private_key_file="$KEYS_DIR/private_key.pem"
    local public_key_file="$KEYS_DIR/public_key.pem"

    # Generate the private key
    openssl genpkey -algorithm RSA -out "$private_key_file" -pkeyopt rsa_keygen_bits:2048 2>/dev/null
    if [[ $? -ne 0 ]]; then
        printf "Error: Failed to generate private key.\n" >&2
        return 1
    fi

    # Generate the public key from the private key
    openssl rsa -pubout -in "$private_key_file" -out "$public_key_file" 2>/dev/null
    if [[ $? -ne 0 ]]; then
        printf "Error: Failed to generate public key.\n" >&2
        return 1
    fi
}

# Main function
main() {
    generate_keys || return 1
}

# Execute main function
main
