import ahmed
import json
import argparse

parser = argparse.ArgumentParser(description='YOLO Prediction Script')
parser.add_argument('--file', type=str, required=True, help='Path to the image file')
parser.add_argument('--output', type=str, required=True, help='Json output file')

args = parser.parse_args()

result = ahmed.get_img(args.file)
# print('------'*50)
# print(result)

x = {}
for i, j in result.items():
    if j and isinstance(j, list) and len(j) > 0 and isinstance(j[0], list) and len(j[0]) > 0 and len(j[0][0]) > 1:
        x[i] = j[0][0][1]
#         print(i, j[0][0][1])
    else:
        x[i] = None  # or some default value
        print(f"Skipping {i} due to unexpected structure or empty list")

with open(args.output, 'w') as json_file:
    json.dump(x, json_file, ensure_ascii=False)

print('success')
