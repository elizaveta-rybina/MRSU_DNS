import { tokens } from "../theme";

/*
 * @property int $id
 * @property string $name
 * @property int $ttl
 * @property int $timeout
 * @property string $soa
 * @property string $admin
 * @property int $serial
 * @property int $refresh
 * @property int $retry
 * @property int $expire
 * @property int $minimum
 * @property int $created_at
 * @property int $updated_at
 */

export const mockDataRecord = [
  {
    id: 0,
    domainId: 1,
    type: "A",
    value: "192.189.123.0",
    priority: 10,
    ttl: 86400
  },
  {
    id: 1,
    domainId: 1,
    type: "A",
    value: "192.189.123.0",
    priority: 10,
    ttl: 86400
  },
  {
    id: 3,
    domainId: 1,
    type: "MX",
    value: "sffs",
    priority: 10,
    ttl: 86400
  },
  {
    id: 4,
    domainId: 1,
    type: "CNAME",
    value: "svsvsv",
    priority: 10,
    ttl: 86400
  },
  {
    id: 5,
    domainId: 1,
    type: "A",
    value: "cssscscs",
    priority: 10,
    ttl: 86400
  },
  {
    id: 6,
    domainId: 1,
    type: "A",
    value: "194.139.125.0",
    priority: 10,
    ttl: 86400
  }
]

export const mockDataDomain = [
  {
    id: 0,
    name: "mrsu.ru",
    ttl: 14400,
    timeout: 86400,
    soa: "ns1.mrsu.ru",
    admin: "lysenkov.mrsu.ru",
    serial: 86400,
    refresh: 3600,
    retry: 7200,
    expire: 1209600,
    minimum: 86400,
  },
  {
    id: 1,
    name: "vestnik.mrsu.ru",
    ttl: 14400,
    timeout: 86400,
    soa: "ns1.mrsu.ru",
    admin: "lysenkov.mrsu.ru",
    serial: 86400,
    refresh: 3600,
    retry: 7200,
    expire: 1209600,
    minimum: 86400,
  },
  {
    id: 2,
    name: "rim.mrsu.ru",
    ttl: 14400,
    timeout: 86400,
    soa: "ns1.mrsu.ru",
    admin: "lysenkov.mrsu.ru",
    serial: 86400,
    refresh: 3600,
    retry: 7200,
    expire: 1209600,
    minimum: 86400,
  },
  {
    id: 3,
    name: "v.mrsu.ru",
    ttl: 14400,
    timeout: 86400,
    soa: "ns1.mrsu.ru",
    admin: "lysenkov.mrsu.ru",
    serial: 86400,
    refresh: 3600,
    retry: 7200,
    expire: 1209600,
    minimum: 86400,
  },
  {
    id: 4,
    name: "ep.mrsu.ru",
    ttl: 14400,
    timeout: 86400,
    soa: "ns1.mrsu.ru",
    admin: "lysenkov.mrsu.ru",
    serial: 86400,
    refresh: 3600,
    retry: 7200,
    expire: 1209600,
    minimum: 86400,
  },
  {
    id: 5,
    name: "led.mrsu.ru",
    ttl: 14400,
    timeout: 86400,
    soa: "ns1.mrsu.ru",
    admin: "lysenkov.mrsu.ru",
    serial: 86400,
    refresh: 3600,
    retry: 7200,
    expire: 1209600,
    minimum: 86400,
  },
  {
    id: 6,
    name: "ino.mrsu.ru",
    ttl: 14400,
    timeout: 86400,
    soa: "ns1.mrsu.ru",
    admin: "lysenkov.mrsu.ru",
    serial: 86400,
    refresh: 3600,
    retry: 7200,
    expire: 1209600,
    minimum: 86400,
  },
];

