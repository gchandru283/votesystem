import cv2
import face_recognition

# Load known faces and their encodings from the database
known_faces = {
    "person1": face_recognition.load_image_file("photo1708399683.jpeg"),
    #"person2": face_recognition.load_image_file("person2.jpg"),
    # Add more known faces as needed
}

for name, image in known_faces.items():
    known_faces[name] = face_recognition.face_encodings(image)[0]

# Start capturing video from the live feed camera
video_capture = cv2.VideoCapture(0)

while True:
    # Capture frame-by-frame
    ret, frame = video_capture.read()

    # Find all face locations and encodings in the current frame
    face_locations = face_recognition.face_locations(frame)
    face_encodings = face_recognition.face_encodings(frame, face_locations)

    # Loop through each face found in the frame
    for face_encoding in face_encodings:
        # Compare face encoding with known faces
        for name, known_encoding in known_faces.items():
            match = face_recognition.compare_faces([known_encoding], face_encoding)
            if match[0]:
                print(f"Match found! Identity: {name}")
                # Perform further actions based on the match
                # For example, display the identity on the frame
                cv2.putText(frame, name, (50, 50), cv2.FONT_HERSHEY_SIMPLEX, 1, (0, 255, 0), 2)
                break

    # Display the resulting frame
    cv2.imshow('Video', frame)

    # Exit the loop if 'q' is pressed
    if cv2.waitKey(1) & 0xFF == ord('q'):
        break

# Release the video capture object and close OpenCV windows
video_capture.release()
cv2.destroyAllWindows()