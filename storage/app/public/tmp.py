import os

folder_path = r"C:\Users\Rohan\Desktop\School\Fontys\Semester 1\Project 5\Code\MotionSync\storage\app\public"

for filename in os.listdir(folder_path):
    if filename.lower().endswith(".png"):
        print(filename)