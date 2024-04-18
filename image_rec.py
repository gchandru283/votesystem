import cv2
import os
import face_recognition

def verify_faces_with_image(verification_image_path):
    print("Starting function")
    timeout = 20
    confidence_threshold = 0.6
    resize_factor = 0.5  # Resize factor for images
    tolerance = 0.6  # Adjust tolerance level for face recognition

    # Validate if the specified file exists
    if not os.path.isfile(verification_image_path):
        print("Error: The specified file does not exist.")
        return False

    # Load the specified image for verification and resize it
    verification_image = cv2.imread(verification_image_path)
    verification_image = cv2.resize(verification_image, (0, 0), fx=resize_factor, fy=resize_factor)
    verification_encoding = face_recognition.face_encodings(verification_image)[0]

    # Accessing webcam
    video = cv2.VideoCapture(0)
    frame_count = 0

    while True:
        ret, frame = video.read()
        if not ret:
            break

        # Resize frame
        frame = cv2.resize(frame, (0, 0), fx=resize_factor, fy=resize_factor)

        # Face detection
        face_locations = face_recognition.face_locations(frame)
        if len(face_locations) == 0:
            continue
        
        face_encodings = face_recognition.face_encodings(frame, known_face_locations=face_locations)

        for face_encoding in face_encodings:
            match = face_recognition.compare_faces([verification_encoding], face_encoding, tolerance=tolerance)
            if match[0]:
                print("Match found with the specified image!")
                video.release()
                cv2.destroyAllWindows()
                print(True)   # Return True if a match is found

    video.release()
    cv2.destroyAllWindows()
    print(False)  # Return False if no match is found

if __name__ == "__main__":
    import sys
    if len(sys.argv) != 2:
        print("Usage: python image_rec.py <verification_image_path>")
        sys.exit(1)

    verification_image_path = sys.argv[1]
    verify_faces_with_image('images/profile.jpg')


# def printf(verification_image_path):
#     print(verification_image_path)

# if __name__ == "__main__":
#     import sys
#     if len(sys.argv) != 2:
#         print("Usage: python image_rec.py <verification_image_path>")
#         sys.exit(1)

#     verification_image_path = sys.argv[1]
#     print(verification_image_path)
