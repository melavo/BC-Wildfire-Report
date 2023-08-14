<a href="javascript:void(0);"
    class="location-popover display-inline-block link-underline"
    data-bs-toggle="popover"
    data-bs-placement="left"
    data-original-title="{{ $lat }}, {{  $lng }}"
    data-map='@gmap([
        "lat" => $lat,
        "lon" => $lng,
        "zoom" => 5,
        "size" => "300x150"
    ])'
    data-bs-content="
        <div class='mb-1 pop-map'></div>
        <table class='table table-striped'>
        @if(isset($fireItem['INCIDENT_NAME']) && $fireItem['INCIDENT_NAME'])
            <tr class='align-middle'>
                <td class='align-middle text-nowrap'>
                    <i class='la la-mountain align-middle'></i> INCIDENT NAME
                </td>
                <td class='align-middle text-nowrap'>
                    <strong>{{ $fireItem['INCIDENT_NAME'] }} m</strong>
                </td>
            </tr>
        @endif
        @if(isset($fireItem['FIRE_NUMBER']) && $fireItem['FIRE_NUMBER'])
            <tr class='align-middle'>
                <td class='align-middle text-nowrap'>
                    <i class='la la-satellite align-middle'></i> FIRE NUMBER
                </td>
                <td class='align-middle text-nowrap'>
                    <strong>{{ $fireItem['FIRE_NUMBER'] }}</strong>
                </td>
            </tr>
        @endif
        @if(isset($fireItem['GEOGRAPHIC_DESCRIPTION']) && $fireItem['GEOGRAPHIC_DESCRIPTION'])
            <tr class='align-middle'>
                <td class='align-middle' colspan='2'>
                    {{ $fireItem['GEOGRAPHIC_DESCRIPTION'] }}
                </td>
            </tr>
        @endif
        </table>
    "
    data-bs-container="body"
    data-bs-trigger="hover"
    data-bs-sanitize="false"
    data-bs-html="true">
    {{ $lat }}, {{  $lng }}
 </a>
