import sys
import json

def main():
    # Ensure the script is called with exactly one argument
    if len(sys.argv) != 2:
        print("Usage: python script.py '<JSON_STRING>'")
        sys.exit(1)

    # Parse the JSON string argument
    json_string = sys.argv[1]
    try:
        json_data = json.loads(json_string)
    except json.JSONDecodeError:
        print("Invalid JSON string")
        sys.exit(1)

    # Print the JSON data
    print(json.dumps(json_data))

if __name__ == "__main__":
    main()
