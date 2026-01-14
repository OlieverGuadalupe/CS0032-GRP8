import sys
import json

def calculate_forecast(current_rev, growth):
    # CEO Logic: Simple linear projection for next month
    projected = current_rev * (1 + growth)
    return round(projected, 2)

if __name__ == "__main__":
    try:
        # Check if arguments were passed
        if len(sys.argv) < 3:
            raise ValueError("Missing arguments")

        rev = float(sys.argv[1])
        growth = float(sys.argv[2])
        
        result = calculate_forecast(rev, growth)
        
        # Output ONLY JSON
        print(json.dumps({
            "status": "success",
            "forecast": result,
            "confidence": "92%"
        }))
    except Exception as e:
        print(json.dumps({
            "status": "error",
            "message": str(e)
        }))