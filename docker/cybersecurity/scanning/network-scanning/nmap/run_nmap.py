import subprocess
import json
from flask import Flask, request, jsonify

app = Flask(__name__)

def run_nmap_command(command):
    try:
        result = subprocess.run(['nmap'] + command, capture_output=True, text=True)
        if result.returncode != 0:
            return {'status': 'error', 'message': result.stderr}
        return {'status': 'success', 'data': result.stdout.split('\n')}
    except Exception as e:
        return {'status': 'error', 'message': str(e)}

@app.route('/nmap/scan', methods=['POST'])
def advanced_scan():
    data = request.json
    target = data.get('target')
    options = data.get('options', [])

    if not target:
        return jsonify({'status': 'error', 'message': 'Target is required'}), 400

    command = options + [target]
    response = run_nmap_command(command)
    return jsonify(response)

if __name__ == '__main__':
    app.run(host='0.0.0.0', port=5000, debug=True)

