import re

class EnvironmentVariables:
    def __init__(self, input_string):
        # Check if the input is a file path or raw string
        if input_string.endswith('.env'):
            with open(input_string, 'r') as file:
                input_string = file.read()
        # Parse the string into a dictionary
        self.variables = self.parse_string_to_dict(input_string)
    
    def parse_string_to_dict(self, input_string):
        # Split the string into lines, then split each line into key-value pairs
        lines = input_string.strip().split("\n")
        variables = {}
        
        for line in lines:
            # Remove extra spaces and ignore empty lines
            line = line.strip()
            if line:
                # Split at the first '=' to separate the key and value
                key, value = line.split("=", 1)
                variables[key.strip()] = value.strip().strip('"')  # Clean up any extra quotes
        
        return variables
    
    def get_value(self, key):
        # Return the value for the given key
        return self.variables.get(key, None)
    
    def get_all_values(self):
        # Return all values as a list
        return list(self.variables.values())
 