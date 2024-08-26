# import subprocess
# from http.server import BaseHTTPRequestHandler, HTTPServer
# import urllib.parse as urlparse

# class NmapHandler(BaseHTTPRequestHandler):
#     def do_GET(self):
#         # Parse the query parameters
#         query = urlparse.urlparse(self.path).query
#         params = urlparse.parse_qs(query)
        
#         target = params.get('target', None)
#         options = params.get('target', None)

#         if target:
#             target = target[0]
#             # Run the Nmap command
#             result = subprocess.run(['nmap', options, target], stdout=subprocess.PIPE)
#             output = result.stdout.decode('utf-8')
            
#             # Send response
#             self.send_response(200)
#             self.send_header('Content-type', 'text/plain')
#             self.end_headers()
#             self.wfile.write(output.encode('utf-8'))
#         else:
#             self.send_response(400)
#             self.send_header('Content-type', 'text/plain')
#             self.end_headers()
#             self.wfile.write(b"Target parameter is required.\n")

# if __name__ == "__main__":
#     server_address = ('', 5000)
#     httpd = HTTPServer(server_address, NmapHandler)
#     print("Starting server on port 5000...")
#     httpd.serve_forever()


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

