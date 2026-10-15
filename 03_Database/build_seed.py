"""
build_seed.py
Kiểm tra danh mục và xuất JSON xem trước.

Chạy bằng Python trên Windows.
Không kết nối hoặc sửa đổi MySQL.
"""

import json
from pathlib import Path

from catalog import MUSEUMS, SCENES, ARTIFACTS


ROOT = Path(__file__).resolve().parent
OUTPUT = ROOT / "catalog_preview.json"


def validate_catalog() -> list[str]:
    errors = []

    museum_codes = [item.code for item in MUSEUMS]
    if len(museum_codes) != len(set(museum_codes)):
        errors.append("Mã bảo tàng bị trùng.")

    known_codes = set(museum_codes)

    for scene in SCENES:
        if scene.museum_code not in known_codes:
            errors.append(
                f"Cảnh {scene.code} tham chiếu bảo tàng không tồn tại."
            )

        if "<BỔ_SUNG_PANORAMA_360>" in scene.panorama_path:
            print(
                f"[CHƯA CÓ MEDIA] Cảnh {scene.code}: "
                "cần bổ sung panorama thật."
            )

        if not scene.is_verified:
            print(
                f"[CHƯA XÁC MINH] Cảnh {scene.code}: "
                "chưa xác nhận nguồn và giấy phép."
            )

    for artifact in ARTIFACTS:
        if artifact.museum_code not in known_codes:
            errors.append(
                f"Hiện vật {artifact.code} tham chiếu "
                "bảo tàng không tồn tại."
            )

        if not artifact.is_verified:
            print(
                f"[DỮ LIỆU MẪU] Hiện vật {artifact.code}: "
                "chưa xác minh nội dung và nguồn."
            )

    return errors


def main() -> None:
    errors = validate_catalog()

    if errors:
        print("\nCó lỗi cần sửa:")
        for error in errors:
            print(f"- {error}")
        raise SystemExit(1)

    payload = {
        "museums": [
            item.__dict__ for item in MUSEUMS
        ],
        "scenes": [
            item.__dict__ for item in SCENES
        ],
        "artifacts": [
            item.__dict__ for item in ARTIFACTS
        ],
        "generated_for": "BaoTang360",
        "data_status": "initial_sample",
    }

    OUTPUT.write_text(
        json.dumps(payload, ensure_ascii=False, indent=2),
        encoding="utf-8",
    )

    print("\nKiểm tra danh mục hoàn tất.")
    print(f"Số bảo tàng: {len(MUSEUMS)}")
    print(f"Số cảnh mẫu: {len(SCENES)}")
    print(f"Số hiện vật mẫu: {len(ARTIFACTS)}")
    print(f"File JSON: {OUTPUT}")
    print("Lưu ý: dữ liệu này chưa được nhập vào MySQL.")


if __name__ == "__main__":
    main()
