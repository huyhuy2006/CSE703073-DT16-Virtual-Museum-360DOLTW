"""
catalog.py
Danh mục khởi đầu cho dự án BaoTang360.

Lưu ý:
- Thông tin danh mục không đồng nghĩa với quyền sử dụng hình ảnh.
- Các cảnh và hiện vật bên dưới là dữ liệu khởi tạo/mẫu.
- Chỉ điền URL nguồn và giấy phép media sau khi xác minh từng tệp.
"""

from dataclasses import dataclass, asdict
from typing import Optional
import json


@dataclass
class Museum:
    code: str
    slug: str
    name: str
    country: str
    city: str
    description_vi: str
    description_en: str
    website: str
    latitude: Optional[float] = None
    longitude: Optional[float] = None


@dataclass
class Scene:
    museum_code: str
    code: str
    slug: str
    title_vi: str
    title_en: str
    panorama_path: str
    panorama_source_url: str = ""
    panorama_license: str = ""
    is_verified: bool = False


@dataclass
class Artifact:
    museum_code: str
    code: str
    title_vi: str
    title_en: str
    description_vi: str
    description_en: str
    image_path: str = ""
    source_url: str = ""
    license_code: str = ""
    is_verified: bool = False


MUSEUMS = [
    Museum(
        code="NATURALIS",
        slug="naturalis-biodiversity-center",
        name="Naturalis Biodiversity Center",
        country="Netherlands",
        city="Leiden",
        description_vi=(
            "Bảo tàng và trung tâm nghiên cứu về đa dạng sinh học "
            "tại Leiden, Hà Lan."
        ),
        description_en=(
            "A biodiversity museum and research center "
            "in Leiden, the Netherlands."
        ),
        website="https://www.naturalis.nl/",
    ),
    Museum(
        code="DHM",
        slug="deutsches-historisches-museum",
        name="Deutsches Historisches Museum",
        country="Germany",
        city="Berlin",
        description_vi=(
            "Bảo tàng Lịch sử Đức tại Berlin, giới thiệu "
            "các chủ đề lịch sử Đức và châu Âu."
        ),
        description_en=(
            "The German Historical Museum in Berlin, "
            "presenting German and European history."
        ),
        website="https://www.dhm.de/",
    ),
    Museum(
        code="RAMSWOERTHE",
        slug="museum-house-rams-woerthe",
        name="Museum House Rams Woerthe",
        country="Netherlands",
        city="Steenwijk",
        description_vi=(
            "Địa điểm bảo tàng trong một ngôi nhà lịch sử "
            "tại Steenwijk, Hà Lan; cần xác minh thông tin "
            "địa điểm và nội dung trưng bày trước khi công bố."
        ),
        description_en=(
            "A museum location in a historic house in Steenwijk, "
            "the Netherlands; verify venue details and collections "
            "before publication."
        ),
        website="",
    ),
]


# Đây là các vị trí mẫu để phát triển ứng dụng.
# Không được coi là tên phòng hoặc panorama thật đã được xác minh.
SCENES = [
    Scene(
        museum_code="NATURALIS",
        code="NAT-SCENE-001",
        slug="canh-mau-01",
        title_vi="Cảnh tham quan mẫu 01",
        title_en="Sample Visit Scene 01",
        panorama_path=(
            "media/panoramas/naturalis-biodiversity-center/canh-01/"
            "<BỔ_SUNG_PANORAMA_360>"
        ),
    ),
    Scene(
        museum_code="DHM",
        code="DHM-SCENE-001",
        slug="canh-mau-01",
        title_vi="Cảnh tham quan mẫu 01",
        title_en="Sample Visit Scene 01",
        panorama_path=(
            "media/panoramas/deutsches-historisches-museum/canh-01/"
            "<BỔ_SUNG_PANORAMA_360>"
        ),
    ),
    Scene(
        museum_code="RAMSWOERTHE",
        code="RAM-SCENE-001",
        slug="canh-mau-01",
        title_vi="Cảnh tham quan mẫu 01",
        title_en="Sample Visit Scene 01",
        panorama_path=(
            "media/panoramas/museum-house-rams-woerthe/canh-01/"
            "<BỔ_SUNG_PANORAMA_360>"
        ),
    ),
]


# Hiện vật mẫu chỉ dùng để phát triển cấu trúc dữ liệu.
# Thay bằng hiện vật có thật và nguồn đáng tin cậy trước khi đưa lên website.
ARTIFACTS = [
    Artifact(
        museum_code="NATURALIS",
        code="NAT-ART-001",
        title_vi="Hiện vật mẫu về đa dạng sinh học",
        title_en="Sample Biodiversity Artifact",
        description_vi=(
            "Nội dung mẫu. Bổ sung tên hiện vật, thông tin khoa học "
            "và nguồn tham khảo đã xác minh."
        ),
        description_en=(
            "Sample content. Add the verified artifact name, "
            "scientific information, and reference source."
        ),
    ),
    Artifact(
        museum_code="DHM",
        code="DHM-ART-001",
        title_vi="Hiện vật lịch sử mẫu",
        title_en="Sample Historical Artifact",
        description_vi=(
            "Nội dung mẫu. Bổ sung mô tả hiện vật dựa trên "
            "nguồn chính thức của bảo tàng."
        ),
        description_en=(
            "Sample content. Add an artifact description based "
            "on the museum's official sources."
        ),
    ),
    Artifact(
        museum_code="RAMSWOERTHE",
        code="RAM-ART-001",
        title_vi="Hiện vật mẫu tại Rams Woerthe",
        title_en="Sample Rams Woerthe Artifact",
        description_vi=(
            "Nội dung mẫu. Cần xác minh hiện vật và bộ sưu tập "
            "thực tế trước khi sử dụng."
        ),
        description_en=(
            "Sample content. Verify the actual artifact and collection "
            "before use."
        ),
    ),
]


def export_catalog(output_path: str = "catalog_preview.json") -> None:
    """Xuất danh mục thành JSON để xem xét trước khi nhập database."""
    payload = {
        "museums": [asdict(item) for item in MUSEUMS],
        "scenes": [asdict(item) for item in SCENES],
        "artifacts": [asdict(item) for item in ARTIFACTS],
        "notice": (
            "Sample catalog only. Verify venue details, artifact data, "
            "media sources, and licenses before publication."
        ),
    }

    with open(output_path, "w", encoding="utf-8") as file:
        json.dump(payload, file, ensure_ascii=False, indent=2)

    print(f"Đã xuất danh mục xem trước: {output_path}")


if __name__ == "__main__":
    export_catalog()
