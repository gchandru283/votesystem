import cv2
import os
import face_recognition
import sys

def verify_faces_with_image(verification_image_path, timeout=20):
    confidence_threshold = 0.4
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

    video = cv2.VideoCapture(0)
    frame_count = 0
    start_time = cv2.getTickCount()

    while True:
        ret, frame = video.read()
        if not ret:
            break

        # Process every nth frame
        if frame_count % 5 != 0:
            frame_count += 1
            continue

        frame = cv2.resize(frame, (0, 0), fx=resize_factor, fy=resize_factor)

        # Face detection
        face_locations = face_recognition.face_locations(frame)
        if len(face_locations) == 0:
            continue
        
        face_encodings = face_recognition.face_encodings(frame, known_face_locations=face_locations)

        for face_encoding in face_encodings:
            match = face_recognition.compare_faces([verification_encoding], face_encoding, tolerance=tolerance)
            if match[0]:
                # print("Match found with the specified image!")
                video.release()
                cv2.destroyAllWindows()
                return True  # Return True if a match is found

        frame_count += 1

        # Check for timeout
        current_time = cv2.getTickCount()
        elapsed_time = (current_time - start_time) / cv2.getTickFrequency()
        if elapsed_time > timeout:
            # print("Timeout reached. No match found.")
            break

        if cv2.waitKey(1) == ord('q'):
            break

    video.release()
    cv2.destroyAllWindows()
    return False  # Return False if no match is found
    
if __name__ == "__main__":
    if len(sys.argv) != 2:
        print("Usage: python image_rec.py <image_path>")
        sys.exit(1)
    
    image_path = sys.argv[1]
    match_found = verify_faces_with_image(image_path)
    print(match_found)